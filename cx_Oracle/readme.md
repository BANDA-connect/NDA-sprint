## Installation and Setup of cx_Oracle python module.

Based in part on https://gist.github.com/kimus/10012910

Installs instantclient_11_2, and cx_Oracle module for python

### Prerequisites

- build-essential, python-dev, libaio-dev
- Oracle instant client (basic and sdk) 11.2 x86_64 version
- cx_Oracle package 5.1.2
- SSH Public Key in stash

## Setup.sh

- Run this as sudo to retreive files and configure global environemnt variables.
- This retreives files from stash if not present, unzips files, creates library link, and sets up global environment variables by placing cx_oracle.sh drop-in in /etc/profile.d/.
- You must log out and back in again, or source /etc/profile.d/cx_oracle.sh for the environment variables to be exported.
- Installs the cx_Oracle python library.
```
bash setup.sh
source /etc/profile.d/cx_oracle.sh
```

## Testing

Load the python interactive shell.

```
python
```

In the shell try importing the cx_Oracle module.

```
import cx_Oracle
```

If working, you should not get any error messages.

## How to Use

https://cx-oracle.readthedocs.org/en/latest/

- TODO: Add some test files with examples. 