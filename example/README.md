# Warning...
This is an UNSUPPORTED PHP MTA (Multi-Target Application) for XS-Advanced.  Please contact the repo owner before using.

# php-mta-example
Multi-Target Application that contains a PHP worker module.  It extends the HANA Academy sample application myapp2.

# Issues
Current known issues.

1. The JWT authentication check does NOT verify the signature at this time, but does some simple checks to see if the JWT is as expected.
2. Connection to the DB via HCI seems to require JDBC style connections.  PHP seems to only support ODBC which should work, but I wasn't able to get the buildpack to a point where I couldprove this.  A fall-back position is to use CURL from the PHP module to query the XSJS module where HCI is used to connect to the DB.  This keeps DB access in one place, but forces changes to the XSJS module if DB access needs change in the PHP module.  There are advantages to both situations, but that is left to the application's designer.
3. Central Logging/Tracing is not yet implemented from PHP.

# Deploying

This archive contains an example external deployment descriptor file.

From parent folder to install the MTA without the external deployment descriptor file.
```
xs deploy myapp2 --use-namespaces
```

From parent folder to install the MTA with the external deployment descriptor file.
```
cp myapp2/mtaext/myapp2_v2.mtaext .
xs deploy myapp2 -e myapp2_v2.mtaext --use-namespaces
```

To remove the MTA.
```
xs undeploy myapp2
```

# Supporting Buildpack

This example MTA project requires an experimental and unsuppored buildpack that can be found here.  
[HANA XSA PHP BuildPack](https://github.com/SAP/hana-xsa-php-buildpack)

