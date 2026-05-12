# Deploying BEED Portal to InfinityFree

## Step 1: Create InfinityFree account
1. Go to https://infinityfree.net and sign up
2. Create a new hosting account
3. Note your MySQL hostname, database name, username, password

## Step 2: Import the database
1. Go to InfinityFree control panel -> MySQL Databases
2. Create a new database
3. Go to phpMyAdmin -> Import -> choose sql/schema.sql -> Go

## Step 3: Update config/database.php
Change the credentials to your InfinityFree MySQL details.

## Step 4: Upload files via FTP
Upload ALL files including vendor/ folder to htdocs/

## Step 5: Update landing.php links
Change /DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/ to / in landing.php

