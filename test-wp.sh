#!/bin/bash

PORT=${PORT:-8080}

docker network ls|grep wordpress >/dev/null
if [ $? -ne 0 ]; then
    docker network create wordpress --driver bridge
fi

docker run --name mysql-wp -d --network=wordpress \
  -e ALLOW_EMPTY_PASSWORD=yes \
  -e MYSQL_USER=wp_user \
  -e MYSQL_PASSWORD=wp_password \
  -e MYSQL_DATABASE=wp_database \
  -e MYSQL_AUTHENTICATION_PLUGIN=mysql_native_password \
  bitnami/mysql:latest

while ! docker logs mysql-wp 2>&1 | grep 'port: 3306 ' >/dev/null; do
  echo "waiting for mysql to start"
  sleep 1
done

docker run --name wordpress -d --network=wordpress \
  -v $PWD:/var/www/html/wp-content/plugins/wordpress-gen2-plugin \
  -e WORDPRESS_DB_HOST=mysql-wp \
  -e WORDPRESS_DB_USER=wp_user \
  -e WORDPRESS_DB_PASSWORD=wp_password \
  -e WORDPRESS_DB_NAME=wp_database \
  -e WORDPRESS_DEBUG=True \
  -p $PORT:80 \
  wordpress:latest

cat <<EOF | docker exec -i wordpress sh
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp
sleep 1
wp --allow-root core install --url=localhost:$PORT --title="WP TEST" --admin_user=test --admin_password=test --admin_email=root@host.docker.internal
wp --allow-root plugin activate wordpress-gen2-plugin
EOF

echo "WP user:pass is test:test"
echo "WP is ready on localhost:$PORT"

( trap exit SIGINT ; read -r -d '' _ </dev/tty ) ## wait for Ctrl-C


docker stop wordpress
docker rm wordpress
docker stop mysql-wp
docker rm mysql-wp
docker network rm -f wordpress
