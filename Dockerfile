# Sử dụng PHP kèm Apache
FROM php:8.0-apache

# Cài đặt extension mysqli để kết nối database (bắt buộc cho dự án PHP thường)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy toàn bộ code vào thư mục web
COPY . /var/www/html/

# Mở cổng 80
EXPOSE 80
