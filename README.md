php-simple-mvc
==============

php simple mvc framework

####  nginx 配置
        server
            {
                listen       80;
                server_name codejm;
                index index.html index.htm index.php;
                root  /dir/codejm;

                location / {
                    try_files $uri $uri/ /index.php?$args;
                }

                location ~ .*\.(php|php5)?$
                {
                    try_files $uri =404;
                    fastcgi_pass  unix:/tmp/php-cgi.sock;
                    fastcgi_index index.php;
                    include fcgi.conf;
                }

                access_log off;
            }
