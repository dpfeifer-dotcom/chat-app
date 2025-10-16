#!/bin/bash

# Ellenőrizzük, hogy a whiptail telepítve van-e
if ! command -v whiptail >/dev/null 2>&1; then
    echo "Install whiptail first (sudo apt install whiptail)"
    exit 1
fi

# ASCII fejléc
clear
echo "#############################################"
echo "#      LARAVEL SAIL MAIL SETUP WIZARD       #"
echo "#############################################"
echo

# Kérdés: akar-e email szolgáltatást
if whiptail --title "Email Service" --yesno "Do you want to configure email service?" 8 60; then
    # IGEN: email mezők bekérése
    MAIL_MAILER=$(whiptail --inputbox "MAIL_MAILER" 8 60 "smtp" 3>&1 1>&2 2>&3) || exit
    MAIL_HOST=$(whiptail --inputbox "MAIL_HOST" 8 60 "smtp.gmail.com" 3>&1 1>&2 2>&3) || exit
    MAIL_PORT=$(whiptail --inputbox "MAIL_PORT" 8 60 "587" 3>&1 1>&2 2>&3) || exit
    MAIL_USERNAME=$(whiptail --inputbox "MAIL_USERNAME" 8 60 "" 3>&1 1>&2 2>&3) || exit
    MAIL_PASSWORD=$(whiptail --passwordbox "MAIL_PASSWORD" 8 60 3>&1 1>&2 2>&3) || exit

    MAIL_ENCRYPTION=$(whiptail --title "Mail Encryption" --radiolist \
    "Choose MAIL_ENCRYPTION:" 10 50 2 \
    "tls" "TLS encryption" ON \
    "ssl" "SSL encryption" OFF \
    3>&1 1>&2 2>&3) || exit

    MAIL_FROM_ADDRESS=$(whiptail --inputbox "MAIL_FROM_ADDRESS" 8 60 "" 3>&1 1>&2 2>&3) || exit

    # Funkció a .env változtatásához
    set_env() {
        local key=$1
        local value=$2
        if grep -q "^$key=" .env; then
            sed -i "s|^$key=.*|$key=$value|" .env
        else
            echo "$key=$value" >> .env
        fi
    }

    # Írjuk be a .env-be
    set_env MAIL_MAILER "$MAIL_MAILER"
    set_env MAIL_HOST "$MAIL_HOST"
    set_env MAIL_PORT "$MAIL_PORT"
    set_env MAIL_USERNAME "$MAIL_USERNAME"
    set_env MAIL_PASSWORD "$MAIL_PASSWORD"
    set_env MAIL_ENCRYPTION "$MAIL_ENCRYPTION"
    set_env MAIL_FROM_ADDRESS "$MAIL_FROM_ADDRESS"
fi

# Sail indítása (email-től függetlenül)
whiptail --msgbox "Starting Laravel Sail..." 8 60
./vendor/bin/sail up -d

# Ha email-t állítottunk be, frissítsük a Laravel config cache-t
if [ ! -z "$MAIL_MAILER" ]; then
    ./vendor/bin/sail artisan config:clear
    ./vendor/bin/sail artisan config:cache
fi
