# Authentication, TFA

In GemsTracker, Authentication consists of two steps:
 - The actual authentication using e.g. username and password
 - A second Two-Factor Authentication (TFA) step

The following authentication methods are or will be provided:
 - GemsTracker login, using organization, username and password
 - EPD login, where a user is logged in by pressing a button within an EPD
 - LDAP (t.b.d.)
 - Radius (t.b.d.)

The following TFA methods are or will be provided:
 - TOTP
 - HOTP via SMS or mail

TFA is disabled for EPD login, and can be disabled for specific IP ranges.

GemsTracker uses [mezzio-session](https://docs.mezzio.dev/mezzio-session/) for session management, see [Session](Session.md).
The authentication methodology is loosely based on principles from [laminas-authentication](https://docs.laminas.dev/laminas-authentication).

## Authentication / log in

### Authentication methods
Each authentication method consists of three class definitions:
- The adapter implementing `\Gems\AuthNew\Adapter\AuthenticationAdapterInterface`. This adapter class is given credentials after which `authenticate()` will determine the validity of these credentials and return a corresponding `AuthenticationResult`.
- The result implementing `\Gems\AuthNew\Adapter\AuthenticationResult`. This class is instantiated in the adapter and reflects a successful or failed login attempt. It may provide access to the logged-in user object.
- The identity implementing `\Gems\AuthNew\Adapter\AuthenticationIdentityInterface`. In case of a successful login, the adapter creates such an identity and passes it into the `AuthenticationResult`. The `AuthenticationService` stores the identity in the session in order to remember the logged-in user in subsequent requests.

These classes have been implemented for GemsTracker login (native database) and Embedded login. Additionally, the `GenericRoutedAuthentication` adapter will provide functionality to login using username and password, after which the authentication is deferred to the correct authentication adapter for the specific user (GemsTracker, LDAP or Radius).

### Authentication Service
After instantiating an authentication method adapter, it can be passed to `AuthenticationService::authenticate()` to log in the user. The `AuthenticationService` also provides functionality for retrieving the identity object and logged-in user, logging out the user and checking the validity of the current login. The `AuthenticationService` class can not be dependency injected, but should be retrieved through the injectable `AuthenticationServiceBuilder` class.

### Authentication Middleware
The `AuthenticationMiddleware` class can be added to endpoints to enforce a user to be logged in. If being logged in is required, but TFA is not necessary, `AuthenticationWithoutTfaMiddleware` can be used. The `NotAuthenticatedMiddleware` can be used to enforce a user is **not** logged in.

### Events
The following events are fired by the authentication service:
- `\Gems\Event\Application\AuthenticatedEvent` after a successful login attempt
- `\Gems\Event\Application\AuthenticationFailedLoginEvent` after a failed login attempt

### Throttling
In order to throttle login attempts, a `LoginThrottle` class instance can be retrieved through the injectable `LoginThrottleBuilder`.

### Configuration
The following configuration is available:
- `session.max_total_time` - overall maximum session time in seconds. After this time, the user must log in again. Defaults to 10 hours
- `session.max_away_time` - maximum time in seconds a user stays logged in after closing the (last) application tab. Defaults to 5 minutes
- `session.max_idle_time` - maximum time in seconds a user stays logged in when idle, i.e. when being inactive while having at least one tab open. Defaults to 20 minutes
- `session.idle_warning_before_logout` - when a user is idle, this parameter defines when the user will see receive a popup notification warning that he will be logged out shortly. Defined as number of seconds before actually logging out automatically. Defaults to 2 minutes
- `session.auth_poll_interval` - when a user is idle, this parameter defines at what interval the open tab should check the current login status, in order to either log out the user or show the warning mentioned above. Defaults to 1 minute, i.e. checking each minute
- `loginThrottle.failureBlockCount` - the maximum number of attempts within the set time period. Defaults to 6
- `loginThrottle.failureIgnoreTime` - time period in seconds, serves as aggregate time interval for `failureBlockCount` as well as waiting time when a block occurs. Defaults to 10 minutes

## TFA

The TFA codebase is split into Adapters, Decorators, Methods:
- Adapters implement the `OtpAdapterInterface` and provide methods to generate and verify TFA codes
- SendDecorators implement the `SendsOtpCodeInterface` and provide a way to send generated TFA (HOTP) codes over a medium (e.g. SMS or mail)
- Methods implement the `OtpMethodInterface` and combine an adapter with optionally a (send) decorator or other specializations. These methods are the actual methods used by the users. The right method for a given user can be obtained using the `OtpMethodBuilder`.

The `TfaService` provides functionality to authenticate, log out and check a users TFA login status.

### Configuration
The following configuration is available:
- `twofactor.methods.{method}.codeLength`
    - `method=AppTotp|MailHotp|SmsHotp`
    - Code length in number of digits
- `twofactor.methods.{method}.codeValidSeconds`
    - `method=AppTotp|MailHotp|SmsHotp`
    - Code validity in seconds. For TOTP, this defines the regeneration period. For HOTP, this defines the maximum time a code is allowed to be used after being generated
- `twofactor.methods.{method}.maxVerifyOtpAttempts`
    - `method=AppTotp|MailHotp|SmsHotp`
    - Maximum number of verification attempts per code (i.e., per `codeValidSeconds`). Defaults to 2
- `twofactor.methods.{method}.maxSendOtpAttempts`
    - `method=MailHotp|SmsHotp`
    - Maximum number of generate/send attempts per time period (`maxSendOtpAttemptsPerPeriod`). Defaults to 3
- `twofactor.methods.{method}.maxSendOtpAttemptsPerPeriod`
    - `method=MailHotp|SmsHotp`
    - Time period for `maxSendOtpAttempts` in seconds. Defaults to 24 hours
