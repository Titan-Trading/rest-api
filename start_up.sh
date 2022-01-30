#!/bin/bash

# Register with service registry (use kafka message bus)
php artisan service:up

# Start service
php-fpm