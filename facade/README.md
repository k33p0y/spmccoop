# Quasar App (facade)

A SPMCECC Project

## Install the dependencies
```bash
yarn
# or
npm install
```

### Start the app in development mode (hot-code reloading, error reporting, etc.)
```bash
quasar dev
```


### Build the app for production
```bash
quasar build
```

### Customize the configuration
See [Configuring quasar.config.js](https://v2.quasar.dev/quasar-cli-webpack/quasar-config-js).

### Update Postgres serial sequence
SELECT setval(pg_get_serial_sequence('election_details', 'id'), coalesce(max(id)+1, 1), false) FROM election_details;
