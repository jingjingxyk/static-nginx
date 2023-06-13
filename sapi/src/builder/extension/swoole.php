<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;
use SwooleCli\Extension;

return function (Preprocessor $p) {
    $depends = ['curl', 'openssl', 'cares', 'zlib', 'brotli', 'nghttp2'];

    $options = ' --enable-swoole --enable-sockets --enable-mysqlnd --enable-swoole-curl --enable-cares ';
    $options .= ' --with-openssl-dir=' . OPENSSL_PREFIX;
    $options .= ' --with-brotli-dir=' . BROTLI_PREFIX;
    $options .= ' --with-nghttp2-dir=' . NGHTTP2_PREFIX;

    if ($p->getInputOption('with-swoole-pgsql')) {
        $options .= ' --enable-swoole-pgsql';
        $depends[] = 'pgsql';
    }

    $ext = (new Extension('swoole'))
        ->withOptions($options)
        ->withLicense('https://github.com/swoole/swoole-src/blob/master/LICENSE', Extension::LICENSE_APACHE2)
        ->withHomePage('https://github.com/swoole/swoole-src')
        ->withManual('https://wiki.swoole.com/#/')
        ->withDependExtension('curl', 'openssl', 'sockets', 'mysqlnd')
        ->withUrl('https://github.com/swoole/swoole-src/archive/refs/tags/v5.0.3.tar.gz')
        ->withFile('swoole-v5.0.3.tar.gz')
        ->withDownloadScript(
            'swoole-src',
            <<<EOF
         git clone -b v5.0.3 --dept=1 https://github.com/swoole/swoole-src.git
EOF
        );
    call_user_func_array([$ext, 'depends'], $depends);
    $p->addExtension($ext);
};
