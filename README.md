# gwyn

service 2 api easily

## Require

	swoole
	
	composer jesusslim/pinject

## Example1

### simple example

	...
	\gwyn\Example\Foo::run();
	
then

	php index.php
	
post url:

	http://localhost:9876?service=test_closure
	
post params:

	a = 3
	b = 4
	
result:

	{
		"status": 1,
		"result": 12
	}
	
### chains example

	...	
	\gwyn\Example\Foo::runMulti();

then

	php index.php
	
post url:

	http://localhost:9876?services=test_class|sum2,test_closure
	
post params:

	a = 15
	
result:

	{
		"status": 1,
		"result": 3200
	}
	
## Example2

see [mobile location example](https://github.com/jesusslim/mobile_location_example)