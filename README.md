# php-tor-filter

Simple and efficient PHP + Bash solution for blocking visitors using Tor network.  

---

## About

**php-tor-filter** is a minimalistic IP-based filter to detect and block incoming connections from Tor exit nodes.  
The detection is based on publicly available lists, updated via a shell script.  
It is designed to be dropped into any PHP-based project and requires no database.

The main purpose is to protect lightweight web applications or panels against anonymous Tor access without using firewalls or advanced reverse proxies.

---

## Features

- Bash script to fetch and merge Tor node IPs from multiple sources
- Fast IP lookup in pure PHP (no `exec`, no `shell_exec`)
- Cookie-based bypass logic for safe IPs and manual override
- Easily extendable
- Works without frameworks or dependencies
- Ready for cron automation

---

## Installation

1. Clone the repository or copy the files manually:

```bash
git clone https://github.com/BuriXon-code/php-tor-filter/
```

2. Place the files:
- `fetch.sh` → on your server (e.g., `/usr/local/bin/fetch.sh`)
- `filter.php` → include at the top of your PHP pages
- `pass.php` → optional script for manual Tor bypass

3. Create a directory to store the IP list, for example:
- `/var/www/html/mypage/tor/`

4. Run the `fetch.sh` script with the target directory:

```bash
fetch.sh /var/www/mysite/tor/
```

5. Add to crontab to refresh the list periodically.

6. In your PHP project:
- Include `filter.php` at the top of any protected script.
- Make sure paths inside `filter.php` are correct:
  - One pointing to `tor-nodes.lst`
  - One pointing to your handler file (e.g. `tor-handler.php`)

7. Prepare files:

>[!NOTE]
> There are a few required adjustments you must make before using the scripts:
>
> - In **`filter.php`**, you need to define the correct path to:
>   - The Tor IP list file (`tor-nodes.lst`)
>   - The handler script that should be executed when a Tor IP is detected
>
> - In **`fetch.sh`**, you must provide a valid directory path as $DIR variable (default, if no parameter specified).
>  This is where the output file `tor-nodes.lst` will be saved.

Without configuring these paths correctly, the scripts will not function as expected.

---

## Usage

**Filtering visitors:**

The `filter.php` script should be included at the very beginning of any PHP page you want to protect — before any HTML output.  
It will automatically check the visitor's IP address and compare it with the list of known Tor exit nodes.

If the visitor is using Tor, they will be immediately redirected to a custom handler page.  
You can use this handler to display a message, deny access, or perform additional steps like CAPTCHA or form verification.

**Allowing Tor users manually:**

If you want to selectively allow certain Tor users to continue, you can include the `pass.php` script inside your handler — after your verification logic.  
For example, after solving a puzzle or entering a token, you can include `pass.php` to mark the session as "passed" and skip future checks.

If you don’t want to allow Tor users at all, simply do not include `pass.php` in your handler — they will remain blocked.

**Updating the Tor IP list:**

The `fetch.sh` script is used to collect and merge fresh lists of Tor exit nodes from multiple sources.  
You must run this script manually or automatically to keep the IP list updated.

To automate the process, add it to your system's crontab.  
Make sure to pass the correct output directory path as the first argument.

Example:
```cron
0 0 * * * fetch.sh /var/www/mysite/tor/ && chown www-data:www-data -R /var/www/mysite/tor/
```
or
```cron
0 0 * * * fetch.sh /var/www/mysite/tor/ && chmod 644 /var/www/mysite/tor/tor-nodes.lst
```

>[!WARNING]
> Setting the correct file owner or permissions is crucial. Incorrect permissions will result in the file being inaccessible.

The script will generate or overwrite a file named `tor-nodes.lst` in the provided directory.
This file is used by `filter.php` during every page load.

---

## Compatibility

- PHP 5.6+
- Bash (tested with Bash 5+)
- curl, awk, grep
- Works on:
  - Termux
  - Debian / Ubuntu / Alpine
  - Shared hosting with basic shell access
  - VPS environments (recommended)

No frameworks or extensions required.  
Compatible with HTTP and HTTPS.

Wanna check if it works? Start a Tor proxy and visit [my webside](https://burixon.dev/).

---

## Support
### Contact me:
For any issues, suggestions, or questions, reach out via:

- **Email:** support@burixon.dev  
- **Contact form:** [Click here](https://burixon.dev/contact/)

### Support me:
If you find this script useful, consider supporting my work by making a donation:

[**DONATE HERE**](https://burixon.dev/donate/)

Your contributions help in developing new projects and improving existing tools!

---
