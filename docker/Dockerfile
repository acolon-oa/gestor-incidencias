FROM nginx:alpine

# Copiamos el index y assets a la raíz de Nginx
COPY index.html /usr/share/nginx/html/
COPY assets /usr/share/nginx/html/assets
COPY pages /usr/share/nginx/html/pages

EXPOSE 80
