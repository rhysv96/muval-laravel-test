# Major issues
These are major flaws I would not want to go to production with
- Password being sent back in the response on login, replace with onlyWith(['email']) to ensure we don't get any sensitive info being sent back
- Task index does not use pagination, it should, this is a performance bottleneck
- Task index calls ->user on every record rather than the more performant ->with eager loading
- Store and update task both use $_POST directly, which is a security flaw and a code quality issue. No validation, and no added security against SQL injections
- Store, update, and delete task build SQL statements with concatenation, which is a huge SQL injection vulnerability. It should be fully replaced with eloquent
- Autoincremented IDs. This can give back way more information about your company than you'd like, as well as making it more trivial for attackers to load all your data out of a vulnerable endpoint. UUIDs should be used. How you migrate to UUIDs depends on the current state of the product. If you're able to fully migrate, you should. Otherwise for a more soft approach you could introduce a 'slug' column, and fetch by that in new endpoints. For simplicity I've gone for the nuclear option, assuming no data, and editing existing migrations directly.
- No email verification. Email verification ensures we can reliably contact the user again to reset their password, as well as ensure its usability as a 2FA option. Furthermore, if they create an account without an email, an attacker could reset their password on their behalf. It's also not compliant with anti-spam laws to not have it
- No password resets, making it impossible for a user to reset their password without support.
- Tasks aren't assigned to an owner, allowing any task to be edited by any user who logs in or registers. I'm unsure what the business logic should be here, so I've left it unaddressed. In a real scenario, I would unwrap this into a concept of boards, boards being owned by users, which other users can join upon invitation. Authorization would revolve around that. I'm considering this to be out of scope.
- Multiple foreign keys are missing, not guaranteeing safety on the database level
- There's a status field in the UI, and it's completely unused in the controller
- There's a user field in the DB, and it's completely unused in many parts of the UI and unused in the controller. I've assumed this is only for task 3 and left it unaddressed
- No errors being displayed in any task forms
- No frontend validation on task forms
- Task forms don't load previously entered values using `old`

# Minor issues
These are smaller issues. They are bugs, but not as big of a concern
- Password length validation on login. I consider users not being able to log in as a P1 issue. They could reset, but it's just a bad experience.
- Use of string over enum on task table, with no validation. I would be remiss to not mention it, so I'm going to mention it, I'm aware of MySQL enums, I'm just not a fan. I've opted to just go for validation on the PHP-side, out of preference.
- no sort order on task index query

# Style changes/DX improvements
These are just changes that improve the developer experience in some way
- Request classes are a bit tidier than validator inline in the controller
- Sail is installed but no Docker compose?
- ide-helper is great for DX, can generate docs for you which PhpStorm picks up and uses in code completion
- No Mailhog! Mailhog is so good, much better than the log driver
- No DB seeder. This is really nice for devs to quickly load up some existing data to work with. E.g. if a dev is working on edit task, they would need to create a task first.
- For larger projects it's really nice to have handlers for business logic, rather than fat controllers. I've skipped this, the handlers I would write would just be eloquent abstractions, which would make for worse code.
- Generate status drop-downs from code, in larger projects it can be easy to miss spots like this
- No base layout
- Namespacing on controllers, it makes it easier to read
- Inconsistent naming in web.php e.g. Route::post and Route::POST
- No handlers, all logic in controllers. Whilst our controller methods are quite small, it's slightly less to do with code quality and more to do with testability. You can't as easily unit test controller methods as you can handler methods. Both are important, however! Due to time, I've only made tasks into handlers. The auth will be replaced with Sanctum, so I figured it wasn't worth my time.
- No docblocks
- I also ran Pint
