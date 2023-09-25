Getting started
===

1) Checkout the repository.
2) Ensure that you have a current version of Docker installed.
3) Execute `make up` from within the project directory.

Running the filters
===

1) Ensure that the container is running via `make up`.
2) Execute the filter command with `make filter method=equal-frequency values=0.1,3.4,3.5,3.6,7.0,9.0,6.0,4.4,2.5,3.9,4.5,2.8` or `make filter method=equal-width values=0.1,3.4,3.5,3.6,7.0,9.0,6.0,4.4,2.5,3.9,4.5,2.8`

Cleaning up
===

When done, simply run `make down`.
