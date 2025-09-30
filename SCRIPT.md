I need a simple deployment setup for my personal Laravel API project. Use Docker and Nginx to serve the app, MySQL for the database, and GitHub Actions for CI/CD. The deployment should:

- Store container on dockerhub and use it inside ec2
- Set correct file permissions for Laravel (storage, bootstrap/cache)
- Run migrations and seeders automatically
- Include a queue worker for Laravel jobs
- Be optimized for performance
- Always clear docker data for fresh deployment


Generate:
1. Dockerfile
2. docker-compose.yml
3. nginx.conf
4. GitHub Actions workflow file (.github/workflows/deploy.yml)

Explain each part briefly and create a .env for this setup and show the folder structure.
