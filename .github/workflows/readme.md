# TEST

```yml

name: Build and Test with Docker Compose

on:
push:
branches:
- main
pull_request:

jobs:
compose-test:
name: Build & Run Docker Compose
runs-on: ubuntu-latest
steps:
- name: Checkout code
uses: actions/checkout@v4

  - name: Set up Docker Buildx
    uses: docker/setup-buildx-action@v3

  - name: Build and start containers with Docker Compose
    run: docker compose -f docker-compose.yml up -d --build

  - name: Wait for containers to be healthy
    run: |
      echo "Waiting for containers to be healthy..."
      docker ps
      sleep 20
      docker ps --format "table {{.Names}}\t{{.Status}}"

  - name: Run Laravel tests inside app container
    run: docker compose exec -T app php artisan test

  - name: Shut down containers
    run: docker compose down -v

```



# PROD

```yml

name: Build and Deploy to AWS EC2

on:
  push:
    branches:
      - main

env:
  DOCKERHUB_USERNAME: ${{ secrets.DOCKERHUB_USERNAME }}
  DOCKERHUB_TOKEN: ${{ secrets.DOCKERHUB_TOKEN }}
  AWS_PRIVATE_KEY: ${{ secrets.AWS_PRIVATE_KEY }}
  EC2_HOST: ${{ secrets.EC2_HOST }}
  EC2_USER: ubuntu
  IMAGE_NAME: johnbibs/laravel-api

jobs:
  build:
    name: Build & Push Docker Image
    runs-on: ubuntu-latest
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Set up Docker Buildx
        uses: docker/setup-buildx-action@v3

      - name: Log in to DockerHub
        uses: docker/login-action@v3
        with:
          username: ${{ env.DOCKERHUB_USERNAME }}
          password: ${{ env.DOCKERHUB_TOKEN }}

      - name: Build and push image to DockerHub
        uses: docker/build-push-action@v5
        with:
          context: ./backend
          file: ./backend/Dockerfile
          push: true
          tags: ${{ env.IMAGE_NAME }}:latest

  deploy:
    name: Deploy to AWS EC2
    needs: build
    runs-on: ubuntu-latest
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Save private key
        run: |
          echo "${{ env.AWS_PRIVATE_KEY }}" > key.pem
          chmod 600 key.pem

      - name: Sync project files to EC2
        run: |
          rsync -avz -e "ssh -o StrictHostKeyChecking=no -i key.pem" ./ ${{ env.EC2_USER }}@${{ env.EC2_HOST }}:/home/${{ env.EC2_USER }}/app

      - name: Deploy with Docker Compose on EC2
        run: |
          ssh -o StrictHostKeyChecking=no -i key.pem ${{ env.EC2_USER }}@${{ env.EC2_HOST }} << 'EOF'
            cd ~/app
            sudo docker login -u ${{ env.DOCKERHUB_USERNAME }} -p ${{ env.DOCKERHUB_TOKEN }}
            sudo docker compose down
            sudo docker compose pull
            sudo docker compose up -d --build
          EOF
          
```