# Annotated Container Amphp HttpServer Demo

This library is a "Hello, World!" type demo showcasing Annotated Container and Amphp http-server. This library is meant to 
showcase the following aspects of [Annotated Container](https://github.com/cspray/annotated-container).

- How you can use Annotated Container bootstrapping to easily create your Container.
- How to configure services, include third-party interfaces, using Annotated Container.
- How to integrate a service like Amp's server using a type-safe configuration.
- How to use factories to create services and not rely on the Container to act as a service locator.
- How you can use custom, semantic attributes to define implementations the Container should be responsible for.
- How you can automatically wire-up "complex" objects, like automatically routing Controllers with no extra configuration or bootstrap changes.
- An example of the logs that are output by Annotated Container

## Installation

Unlike most packages I maintain this library is _not_ meant to be installed via Composer. You should clone this repo and run it directly.

```shell
cd /your/workspace
git clone https://github.com/cspray/annotated-container-amp-http-server-demo.git
cd annotated-container-doctrine-demo && composer install
```

## Usage Guide

Using this app is simple. Run `php app.php`, then visit the following URLs:

- http://localhost:1337/
- http://localhost:1337/amp
- https://localhost:1338/
- https://localhost:1338/amp

## Considerations

This app is not meant to be a truly production-ready app. It is missing tests, would need to provide more functionality,
and made some design decisions intentionally to showcase Annotated Container functionality. What's of real import here
isn't what you can do with the app but the perceived "cleanliness" of the code. Please give it a review! Of particular
import:

- No YAML or JSON configurations. The configuration that is present is minimal and primarily points to the code that has Attributes on it.
- Amp's `HttpServer` is created through a Factory that takes in a type-safe Configuration object.
- Controllers are created and shared with the Container, but we use a custom Attribute, `#[Controller]` to mark it.
- The Controller Attribute includes routing information and routes are autowired. Add a new `RequestHandler` instance, mark it with `#[Controller]`, then restart your server.
- The `data/logs/annotated-container.log` file details extensive information about the compilation and Container creation process.