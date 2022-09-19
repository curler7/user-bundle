Curler7UserBundle
============

The Curler7UserBundle is a Symfony bundle handle user management for API Platform.

### To Do:
- Config serialization group - permission set by parameter
- Config resource by merge files
- Entity all getter / setter over traits
- Need canonical properties?

### Features include:
- Users stored in database or on custom way
- Registration support, with optional email confirmation
- Password forgot support over login link
- Share authentication support over login link / fast invalidate all login links by end user
- Open API docs and authentication
- Easy enable/disable config (validation, serialization, api resource, mapped superclass)
- Many events dispatched
- At least one enabled user with role "ROLE_SUPER_ADMIN" in system validator
- Advanced API Platform context groups
- CLI commands
- 23 languages
- API Unit tested
- Containerized for easy development

### Storage
- ORM entities

## Documentation

### Usage
[docs/usage.md](https://github.com/curler7/user-bundle/blob/master/docs/usage.md)

### Security config
[docs/security_config.md](https://github.com/curler7/user-bundle/blob/master/docs/security_config.md)

### API resource override
[docs/api_resource_override.md](https://github.com/curler7/user-bundle/blob/master/docs/api_resource_override.md)

### Config reference
[docs/config_reference.md](https://github.com/curler7/user-bundle/blob/master/docs/config_reference.md)

### Upgrading
[docs/upgrading.md](https://github.com/curler7/user-bundle/blob/master/docs/upgrading.md)

## Licence
[LICENCE](https://github.com/curler7/user-bundle/blob/master/LICENSE)