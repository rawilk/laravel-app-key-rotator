---
title: Basic Usage
sort: 1
---

Everything is done via the `app-key-rotator:rotate` artisan command. When the command is ran, it'll modify your `.env` file
and add `OLD_APP_KEY=your-old-app-key` to it. If something goes wrong with your app key rotation, you can always revert
back to your old app key from your `.env` file. This is also used in case decryption fails with the new app key; it'll try
and decrypt values with the previous app key. 

See this for more information: [https://gist.github.com/themsaid/ef376d7642be69c1110a0a49b0beb0ea](https://gist.github.com/themsaid/ef376d7642be69c1110a0a49b0beb0ea)

## Command

```bash
php artisan app-key-rotator:rotate
```

{.tip}
> You should probably make a backup of your `.env` file before running this command and rotating your app key.
