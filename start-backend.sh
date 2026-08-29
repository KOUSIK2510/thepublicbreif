#!/bin/bash
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
nvm use 18 --silent 2>/dev/null

export MONGO_URI="mongodb+srv://25kousik10_db_user:JXWLPoOx3MlL8JEc@cluster0.k1vvewt.mongodb.net/thepublicbrief?retryWrites=true&w=majority&appName=Cluster0"
export JWT_SECRET="thepublicbrief_super_secret_jwt_key_2024"
export PORT=3001
export NODE_ENV=development
export WORDPRESS_URL="https://thepublicbrief.in"

echo "Starting The Public Brief backend..."
echo "MongoDB: cluster0.k1vvewt.mongodb.net"
node /Users/kousiksarmalakkaraju/thepublicbreif/backend/server.js
