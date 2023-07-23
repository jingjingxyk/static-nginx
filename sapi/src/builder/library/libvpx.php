<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $libvpx_prefix = LIBVPX_PREFIX;
    $lib = new Library('libvpx');
    $lib->withHomePage('https://chromium.googlesource.com/webm/libvpx')
        ->withLicense('https://chromium.googlesource.com/webm/libvpx/+/refs/heads/main/LICENSE', Library::LICENSE_SPEC)
        ->withManual('https://chromium.googlesource.com/webm/libvpx/+/refs/heads/main')
        ->withFile('libvpx-v1.13.0.tar.gz')
        ->withDownloadScript(
            'libvpx',
            <<<EOF
            git clone -b v1.13.0  --depth=1  https://chromium.googlesource.com/webm/libvpx
EOF
        )
        ->withBuildLibraryCached(false)
        ->withPrefix($libvpx_prefix)
        ->withCleanBuildDirectory()
        ->withCleanPreInstallDirectory($libvpx_prefix)
        ->withConfigure(
            <<<EOF
            mkdir -p build
            cd build
            ../configure --help
            ../configure \
            --prefix={$libvpx_prefix} \
            --disable-shared \
            --enable-static \
            --enable-vp8 \
            --enable-vp9 \

EOF
        )
        ->withPkgName('ssl')
        ->withBinPath($libvpx_prefix . '/bin/')
        ->withDependentLibraries('openssl')
    ;

    $p->addLibrary($lib);

    $p->withVariable('CPPFLAGS', '$CPPFLAGS -I' . $openssl_prefix . '/include');
    $p->withVariable('LDFLAGS', '$LDFLAGS -L' . $openssl_prefix . '/lib');
    $p->withVariable('LIBS', '$LIBS -lssl ');
};
