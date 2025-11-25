# Sử dụng PHP kèm Apache
FROM php:8.0-apache

# Cài đặt extension mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy toàn bộ code vào thư mục web
COPY . /var/www/html/

# --- THÊM ĐOẠN NÀY ĐỂ CẤP QUYỀN GHI FILE ---
# 1. Tạo thư mục uploads nếu chưa có
RUN mkdir -p /var/www/html/uploads

# 2. Chuyển quyền sở hữu thư mục html cho user của Apache (www-data)
RUN chown -R www-data:www-data /var/www/html

# 3. Cấp quyền đọc/ghi (755)
RUN chmod -R 755 /var/www/html
# ---------------------------------------------

# Mở cổng 80
EXPOSE 80