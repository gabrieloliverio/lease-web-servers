# lease-web-servers

## To run local server

```
composer install
php -S localhost:8000 public/index.php
```

## Remote address

The application was deployed to a free-dyno in Heroku, which takes some seconds to warm up - please be patient :)

### UI: 
https://lease-web-servers.herokuapp.com/

### API:

#### Filters:
- https://lease-web-servers.herokuapp.com/api/filters/storage
- https://lease-web-servers.herokuapp.com/api/filters/ram
- https://lease-web-servers.herokuapp.com/api/filters/hard-disk-type
- https://lease-web-servers.herokuapp.com/api/filters/location

#### Server search:
https://lease-web-servers.herokuapp.com/api/servers/?storage=&ram=&hard_disk_type=&location=

# Notes

Unfortunately I wasn't able to do everything I wanted for this project due to time restriction. Some of the things I wanted to do are:

-[ ] Add OpenAPI support
-[ ] Docker + docker-compose
-[x] Create an iterator for the servers obtained from the spreadsheet
-[ ] Use this iterator to iterate just once to filter the servers (I'm iterating twice now - first to process the spreadsheet to produce Storage objects, second to filter the objects)
-[ ] Implement collections/generics to return servers and to pass ram memory objects to constructors
-[ ] I was not able to implement a range with specific values for the storage, the HTML 5 range input is not able to do exactly what was needed, so I used a select element

*Edit: the checked items were done after the code challenge's delivery, accomplished only for personal challenge*