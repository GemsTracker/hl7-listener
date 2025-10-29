FROM adminer:latest
USER root
RUN apk add --update --no-cache openssh
USER adminer