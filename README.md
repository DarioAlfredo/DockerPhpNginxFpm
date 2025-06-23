# DockerPhpNginxFpm

This project provides a simple PHP development environment using Docker, Nginx, and PHP-FPM.

## Prerequisites
- [Docker](https://www.docker.com/products/docker-desktop) installed on your machine
- (Optional) [Docker Compose](https://docs.docker.com/compose/) if not included with Docker

## Project Structure├── docker-compose.yml
├── nginx/
│   └── default.conf
├── php/
│   └── Dockerfile
├── www/
│   ├── data.csv
│   ├── index.php
│   ├── q4_form_submission.php
│   ├── q6_safe_includes.php
    ├── q7_read_csv.php
│   └── pages/
│       ├── about.php
│       ├── admin.php
│       ├── contact.php
│       ├── home.php
│       └── products.php
## How to Deploy

1. **Clone this repository** (if you haven't already):git clone https://github.com/DarioAlfredo/DockerPhpNginxFpm
cd DockerPhpNginxFpm
2. **Build and start the containers:**docker-compose up --build   This will build the PHP-FPM image and start both the Nginx and PHP containers.

3. **Access your application:**
   - Open your browser and go to http://localhost:8080
   - The default port is `8080` (see `docker-compose.yml`). You can change this if needed.

4. **Stopping the environment:**docker-compose down
## Customization
- Place the PHP files in the `www/` directory.
- Nginx configuration is in `nginx/default.conf`.
- PHP extensions can be added in `php/Dockerfile`.

## Troubleshooting
- If you change the Dockerfile or Nginx config, re-run with `docker-compose up --build`.
- Make sure port 8080 is not in use by another application.
- Logs can be viewed with:docker-compose logs
---

This setup is suitable for local development and testing. For production, further security and performance tuning is recommended.