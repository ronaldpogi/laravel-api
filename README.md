### Local Development
When you want to develop locally you will run the Laravel app and connect to your Dockerized database and redis.

### Run using Docker
When you're finished with the development to run the app with Docker to the following
1. Change the `DB_HOST` env variable to `db` (db is the container service name)
2. Change the `REDIS_HOST` env variable to `redis` (redis is the container service name)
3. Build the containers
> docker compose up --build --detach
4. Go to `http://localhost`

### Going to production!
There are minimal changes needed when going to production.

You should change the nginx.conf to match your website URL and add SSL so that you can have an encrypted connection (HTTPS). Always USE PORT 443.

# PINT
* composer require --dev laravel/pint
* ./vendor/bin/pint --parallel

# SERVE
* php artisan serve --host=0.0.0.0 --port=80

# SSH FIX AFTER ELASITIC IP CHANGE
* ssh-keygen -R <IP_ADDRESS>

# DOCKER INSTALL EC2
```bash

# 0) Become root for setup (optional)
sudo -s

# 1) System prep
apt-get update -y
apt-get upgrade -y
apt-get install -y ca-certificates curl gnupg lsb-release

# 2) Add Docker’s official GPG key and repo
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg \
  | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
chmod a+r /etc/apt/keyrings/docker.gpg

echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] \
  https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo $UBUNTU_CODENAME) stable" \
  | tee /etc/apt/sources.list.d/docker.list > /dev/null

# 3) Install Docker Engine + CLI + Compose plugin
apt-get update -y
apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# 4) Enable and start
systemctl enable docker
systemctl start docker

# 5) Allow your user to run docker without sudo (re-login after this)
usermod -aG docker ubuntu   # replace 'ubuntu' if your username is different

# 6) Basic health checks
docker --version
docker compose version

# 7) (Optional) Harden containerd defaults
mkdir -p /etc/containerd
containerd config default > /etc/containerd/config.toml
systemctl restart containerd

```
