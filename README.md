# wpdev-local-plugin

## Bash file to create docker path

```sh
# ./save-wp-env-path.sh
#!/bin/bash
# Ensure the directory exists
mkdir -p ./local/uploads/info-to-pass

# Save the WP_ENV path to the file
echo "Saving wp-env path..."
echo $WP_ENV_PATH > ./local/uploads/info-to-pass/wp-env-path.txt

```

## Make sure it's executable

    chmod +x save-wp-env-path.sh
