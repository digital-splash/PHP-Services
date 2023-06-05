echo "=== Building php-services-base Image"
docker build ./dockerfiles/php-services-base -f ./dockerfiles/php-services-base/Dockerfile -t php-services-base:latest --no-cache
echo "=== php-services-base Image Build Successfully"
