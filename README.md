docker-compose build
docker-compose up -d
docker ps -a
docker exec -it pokemon_tournament_app symfony console doctrine:migration:migrate --no-interaction