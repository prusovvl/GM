<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$Private = "-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEA1N/zBbcJdopHFwLfkufQObWS5ug+cn2+qOkHulEdKaL7gmV+
jG5qRGxmxPAryMLDX1PUw1zK6tngRcIM4LHep0lr/GCGAo+LDOJmWsBay0JInkNr
PBlHE72m/68HY0/hFbetreuyKOptifxxqtvwv7PQa2S/DvDQjkQCcL95MveTlqZR
yZawHvd8DBhmODuxEsYwUs2g8Uki9Cizv3wVW3PmH3UdWPYAXaahKUJByIGF378H
/J/Q0lsFt+3PYVJIIfT6o52+xNpivGISBFYJRXVq5xlAolHIF15IBU8EhS/22QWZ
L2IurrYDyQU9TfJmpyVFfszfzu1bpb/aI7DK/wIDAQABAoIBACV/LTecfjY7NTqA
bxFZc/w2V6IE1rskGyl1or7W+uQSqWrWpevmpyFWVuPpykyawf9QrPGcrsGfjzm6
bLpHmoitDjBucCyTTFMArjbeGyAilNgL2B/F9BcuGPSAyDReJa/Fovo3wjIi2Rit
Nvh5tXaPHe9M17qyAIqOwD9L+vVXBJKXOVAFbCo1BwEyApkXpTBpZXVpitF5WIwj
NXMbsJYTVym+0XgKS4y84kxj/0F40KgkUmfJe1P6W5Rh4obFr/hMFyX6hh0T0D6F
ZueRMU2dFaH21TacazRnOhoJbCkYh2LbwVx9XAJoRr9oHxcPwTlSxUSSyXmJXs6Z
T7Kj6AECgYEA6aUScOb0TNdy7iuMdQSvQQaBy5bX4o6YTp4XfNUhHxeNs/nIWyT6
6yNPhlBQERMliHyGNw79lHzr/RG9sW3T98V4Gm0KRKaSmTlqqRkITLOyejTVex5k
wUmx6dAzxMuFPIdfioE2hfY0ZoqqBGYDacuWOstaMipY0zWTKpaSgIECgYEA6T4i
QCuTv1zHw9lAGtLPtqe1RbbWVluAh5MDKgCBjhZlSAhyf1hxgxCjxD52GeEAC48b
15QiQVuEVSPZY3sHTQkEErqH2tCIbFIN0gMS/sTkCya5Dnydr1Ks7vHKZAnAXGUY
CYeziuvcERkgLb9VKyMkoM2Gl175HaOezAa9i38CgYEAlwyKBIW7UOZEvidXUduq
dY6AYR9vCGAJpWHda23aUOCIUt9cIViiA1w7sGjBqphHPprKplPBqrqsUlqi2U9Y
pyl5wRPXfJR32ClfhFzTmcreytTXYxY+Kxu6sp9QqJyu0XfvU3q/xhFqxRRGYDuX
X1EoGXCYKjBoZnuPXgwkQIECgYBEDshJty12ciksIZFOAmNvpcIEJJSoQCzh4mjS
8bDb9/b3rNdUB0ef+dP/aEZnyLqNpOV34xaXwY98lGhTWcvIk6/nkxwfIigci1jT
BeieBG+SPWtUKdKNHO+vDUiEJkBF9Y2tAjbGe4oMRyuXjbPZUXjXnwD93E2Oc3bb
i7/1GwKBgH9RO1VdbgU5jDdAxlxlUw9IA/3L4/cB9ZyA981FkiuNZakxCqhn++Gu
owlAQeyoN7IZ/wpVo5mjsnUOwEaRIvTMAj6p9FGQyvuC7+YoCZ8Gib1P8Y8UbSBl
IzN3/S21C7BqKkMvQvkXqblLZgumiSMlpdKwL0kUIizePkGHMlq3
-----END RSA PRIVATE KEY-----";


$Public = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1N/zBbcJdopHFwLfkufQ
ObWS5ug+cn2+qOkHulEdKaL7gmV+jG5qRGxmxPAryMLDX1PUw1zK6tngRcIM4LHe
p0lr/GCGAo+LDOJmWsBay0JInkNrPBlHE72m/68HY0/hFbetreuyKOptifxxqtvw
v7PQa2S/DvDQjkQCcL95MveTlqZRyZawHvd8DBhmODuxEsYwUs2g8Uki9Cizv3wV
W3PmH3UdWPYAXaahKUJByIGF378H/J/Q0lsFt+3PYVJIIfT6o52+xNpivGISBFYJ
RXVq5xlAolHIF15IBU8EhS/22QWZL2IurrYDyQU9TfJmpyVFfszfzu1bpb/aI7DK
/wIDAQAB
-----END PUBLIC KEY-----";
$keyPassphrase = "";
$keyCheckData = array(0=>$Private,1=>$Public);
$result = openssl_x509_check_private_key($Public,$keyCheckData);

echo "-----".$result;

?>