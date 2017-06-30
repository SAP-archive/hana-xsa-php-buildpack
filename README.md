# HANA XS Advanced PHP Buildpack

### PHP Buildpack Info and Disclaimers
This Buildpack adds support for modules based on PHP code to a HANA XS Advanced system.  If you want to deploy your PHP to a Cloud Foundry based multi-tenant system such as SAP Cloud Foundry Cloud Platform or Pivotal Cloud Foundry , this buildpack WILL NOT WORK!  For those Cloud Foundry based deployment environments, use the community PHP Buildpack documented at [CloudFoundry.org PHP Buildpack](https://docs.cloudfoundry.org/buildpacks/php/).

This PHP Buildpack is based on PHP Version 5.6.13.  Note that this decidedly older than the current stable version 7.1.6 or even the most recent stable version of the 5 series which is 5.6.30 (versions noted here are current as of 2017-06-29).  The reason for this is that SAP enforces a strict open source approval policy that applies to publically released software and version 5.6.13 is currently the most recent SAP approved version PHP.  In the future newer versions may be approved and this buildpack updated.  However, this is beyond the ability of the contributors to control and as a result NO REQUESTS FOR UPDATING THE PHP VERSION CONTAINED IN THIS BUILDPACK WILL BE HONORED.  You may however, use this buildpack for your own exploration and are encouraged to do so should your needs require a different PHP version.

Also note that this buildpack is not feature complete and may not be suitable for your purpose.  It is intended as a simple example of buildpack construction and can not be guaranteed for any particular fitness of purpose.  Please use it at your own risk and discretion.

### What is a buildpack?
Buildpacks are a convenient way of packaging framework and/or runtime support for your application. The buildpack defines what happens to your application after being pushed and how it is executed.

### Structure
A minimum buildpack contains three main scripts, situated in a folder named bin.
#### bin/detect
The detect script is used to determine whether or not to apply the buildpack to an application. The script is called with one argument, the build directory for the application. This is the directory, where the pushed content is copied after "xs push". The script returns an exit code of 0 if the application can be supported by this buildpack. If the returned exit code is not 0, XSA tries other installed buildpacks by calling their "bin/detect" script.
#### bin/compile
The compile script builds the droplet that will be run by the execution agent and will therefore contain all the components necessary to run the application. The script is run with two arguments, the build directory for the application and the cache directory, which is a location the buildpack can use to store assets during the build process.
#### bin/release
The release script provides feedback metadata back to XSA indicating how the application should be executed. The script is run with one argument, the build location of the application (which was prepared in the compile step). The script must generate a YAML file in the following format:
```yml
config_vars:
    name: value
default_process_types:
    web: commandLine
```
Where config_vars is an optional set of environment variables that will be defined in the environment in which the application is executed. commandLine is the actual command that will be used to start the application. It will be invoked in the work directory prepared in the compile step. 

# Instructions for the PHP buildpack

1. Buildpack PHP runtime - For this example we are using a "portable" PHP runtime for the operating system where XSA is running. This runtime can be used directly after being unpacked. We are copying the packed runtime to a directory inside the buildpack (runtime/PHP.TGZ).
1. Implement bin/detect - We are able to handle the pushed content if it contains a file with the name "index.php". In this case the return code is 0.
2. Implement bin/compile - In this step we extract our PHP runtime (located under runtime/PHP.TGZ) provided as part of our buildpack. Everything lands in the work directory.
3. Implement bin/release - Here we invoke the PHP interpreter's built in single threaded webserver in the PHP runtime we unpacked in the previous step: ```./bin/php/bin/php -S 127.0.0.1: \$VCAP_APP_PORT``` where VCAP_APP_PORT is a port number under which XSA will expect the application to run. This port will be mapped to the XSA application URL.  The server will try to serve the index.php file found in it's document root.
4. Use "xs create-buildpack" to upload the buildpack to XSA. 
```
xs create-buildpack phpbp -p hana-xsa-php-buildpack 10
```
5. Use "xs push" to deploy and run the example application.  
```
xs push phpapp -p php-test
```
6. Open the browser and try the app: 
```
<app_url>
```
should return 
```
PHP-Test with PHP version: 5.6.13<br />Server Time is: 02/06/2016 == 16:16:17<br /> and the output of phpinfo()
```
7. Use "xs push" to deploy and run the second example application.  
```
xs push gdphpapp -p php-gd
``` 
Which creates an image, writes the date on it (graphically) and returns it as an image/png.

# Notes on creating the PHP buildpack

// I decided to try and do this building on the SP12 XS-A server itself instead of on my MacOSX machine because of library compatibilities.
// Theoretically it would be possible under a cross-compiling setup.

// Get the Unix build tools (I don’t know what you’d do for this in a cloud environment)
```
sudo zypper install --type pattern devel_basis
```

```
cd /home/ec2-user/buildpacks
```

// Unzip the latest Stable TAR
```
tar xvf php-5.6.13.tar

cd  php-5.6.13
```

// 'configure' configures this package to adapt to many kinds of systems.
```
./configure --help
```

// Run the configure script and include any build-time options you think you’ll need.
```
./configure --enable-static --enable-cli --disable-all
./configure --enable-static --enable-cli --disable-all --with-gd
```

// This is the version currently in use JSON and openSSL for JWT and CURL for outbound HTTPs
```
./configure --prefix=/home/ec2-user/buildpacks/rootdir/bin/php --exec-prefix=/home/ec2-user/buildpacks/rootdir/bin/php --enable-static --enable-cli --disable-all --with-gd --enable-json --with-openssl --with-curl
```

```
make
make install
```

//Check the PHP binary file to see if it has any external library dependencies by using chroot.

```
sudo chroot rootdir
cd /bin/php/bin
./php -v (should just give version and not error loading any shared libraries.)
```

// If there are shared library issues, exit from bash and run ldd on the php binary.

```
ldd rootdir/bin/php/bin/php
```

// Copy the missing shared libraries into the likewise proper place in the rooter.

```
cp /usr/lib64/libpng12.so.0 rootdir/usr/lib64
```

// Check the PHP binary again with chroot.  Repeat above steps as needed to remove shared library issues.

// Packing up PHP for buildpack usage:

```
cd /home/ec2-user/buildpacks/rootdir
tar czvf ../php-buildpack/runtime/PHP.TGZ .
```

// If an existing buildpack of the same name exists, update it with:  
```
xs update-buildpack phpbp -p php-buildpack
```

```
cd  /home/ec2-user/buildpacks

xs create-buildpack phpbp php-buildpack 10
xs push phpapp -p php-test
```

# Example

This buildpack can be exercised with the following sample Multi-Target-Application found in the example folder.  
See the [README](/example/README.md) located in the example folder.
