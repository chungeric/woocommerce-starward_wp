# Pre-configured WP install for Starward

Bootstrapped WP theme with Starward plugin dependencies running on the Birdbrain Hibiki framework powered by roots.io.

This repo is not for public use and should be contained within the // and Birdbrain offices as it contains premium plugins including ACF Pro.

## Getting started

- Install [WordPress command line tools (WP CLI)](http://wp-cli.org) because it's the tits.
- Create and change into the new project directory and install WordPress by running the following set of commands

#### Download the latest version of WordPress
```
wp core download
```
#### Create a new wp-config.php file
```
wp core config --dbname=database_name --dbuser=database_user --dbpass=database_pass
```
#### Create the database based on wp-config.php
```
wp db create
```
#### Install WordPress

```
wp core install --url=http://localhost/directory_name --title="site_title" --admin_user=username --admin_email=someone@birdbrain.com.au --admin_password=password
```

## Install Starward dependencies

- Within the project directory initialise a new Git repository, pull the boilerplate Starward WP repository and enable the plugin dependencies and enable pretty permalinks and flush the WP rewrites table to generate the Hibiki .htaccess

#### Initialise a new repository and pull the Starward WP repo

```
git init
git remote add starward_wp git@github.com:distilagency/starward_wp.git
git pull starward_wp master
```

#### Activate the plugin dependencies for Starward
```
wp plugin activate --all
```

#### Activating Hibiki and completing the activation process
- Login to your new WP backend `http://localhost/directory_name/wp-admin/` using the username and password used before
- Once logged in activate Hibiki by navigating to `Appearance => Themes`
- This will take you to the Hibiki activation screen, leave all of the items set to 'Yes' and click 'Save Changes'. This will create a dummy homepage, navigation, update the permalinks and complete the WP preparation for Starward :boom:

#### Final Step: Syncing ACF for the Starward component based build
- Click the 'Custom Fields' link in the WordPress menu
- Below the title 'Field Groups' click the link 'Sync available'
- Sync both 'Page' and 'Settings' field groups
- The ***Settings*** field group will populate the link in the Wordpress admin menu with the site name you declared above. Each of these setting items are available via the Starward API (`:GET api/settings`)
- The ***Page*** field group creates the base of a [flexible content](https://www.advancedcustomfields.com/resources/flexible-content/) field, each child field is accessible within the API (`:GET api/page?slug=pageslug`). The 'child block' name becomes the component name within Starward (make sure it's in capital case) and each field within the 'child block' becomes accessible as a prop in Starward. Sometimes code is easier so [view here](https://github.com/distilagency/starward/blob/master/app/components/Page/PageContent.jsx#L9-L24) and [here](https://github.com/distilagency/starward/blob/master/app/components/Acf/Layout/index.js) and [here](https://github.com/distilagency/starward/tree/master/app/components/Acf/Layout)
