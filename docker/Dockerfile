# Use the official Ubuntu 22.04 as a base image
FROM ubuntu:22.04

# Set the maintainer label
LABEL maintainer="yuancheng@comp.nus.edu.sg"

# Prevent interactive prompts from appearing during package installation
ARG DEBIAN_FRONTEND=noninteractive

# Update the package list and install essential packages
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y \
    build-essential \
    curl \
    wget \
    git \
    vim \
    clang-12 \
    autoconf \
    bison \
    re2c \
    libxml2-dev \
    libsqlite3-dev \
    tmux \
    pkg-config \
    sudo \
    gcovr \
    lcov \
    zip \
    tmux \
    sqlite3 \
    valgrind \
    php8.1-dev \
    php-pear \
    python3 \
    python3-pip \
    libpng-dev \
    libonig-dev \
    libssl-dev \ 
    libbz2-dev \
    libcurl4-openssl-dev \
    libenchant-2-dev \
    libgmp-dev \
    libldap-dev \
    libedit-dev \
    libmm-dev \
    libsnmp-dev \
    libsodium-dev \
    libzip-dev \
    libxslt-dev \
    libwebp-dev \
    libjpeg-dev \
    libfreetype-dev \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Add a default user "phpfuzz" with password "phpfuzz" and add to sudo group
RUN useradd -m phpfuzz && \
    echo "phpfuzz:phpfuzz" | chpasswd && \
    usermod -aG sudo phpfuzz

RUN pecl install ast

RUN echo "extension=ast.so" >> /etc/php/8.1/cli/php.ini

WORKDIR /home/phpfuzz/WorkSpace

RUN chown -R phpfuzz /home/phpfuzz/WorkSpace

USER phpfuzz

# Define the default command
CMD ["/bin/bash"]