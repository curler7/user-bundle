Security Config
============

### Config
``` yaml
# config/packages/security.yaml
security:
    enable_authenticator_manager: true
    password_hashers:
        Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: 'auto'
    providers:
        users:
            entity:
                class: App\Entity\User
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        login:
            pattern: ^/api/login
            stateless: true
            json_login:
                check_path: /api/login_check
                success_handler: lexik_jwt_authentication.handler.authentication_success
                failure_handler: lexik_jwt_authentication.handler.authentication_failure

        api:
            login_link:
                check_route: login_check
                signature_properties: [ 'id', 'email', 'enabled', 'verified', 'share' ]
                max_uses: 1
                success_handler: Curler7\UserBundle\Security\Authentication\AuthenticationSuccessHandler
            stateless: true
            jwt: ~

    access_control:
        - { path: ^/, roles: IS_AUTHENTICATED_ANONYMOUSLY }

    role_hierarchy:
        ROLE_ADMIN:       [ROLE_USER]
        ROLE_SUPER_ADMIN: [ROLE_ALLOWED_TO_SWITCH, ROLE_ADMIN]
```

**Note:**
> Provider with no property defined, because bundle handle that. The properties are defined in "Curler7\UserBundle\Model\UserInterface::IDENTIFIERS" array

**Note:**
> Access control need role "IS_AUTHENTICATED_ANONYMOUSLY", because of sharing login link is behind firewall

### Routes
``` yaml
# config/routes/security.yaml
api_login_check:
  path: /api/login_check
  methods: ['POST']

login_check:
  path: /login_check
```