# mwood
# Changer l'URL du site
UPDATE wp_options
SET option_value = replace(option_value, ':old', ':new')
WHERE option_name = 'home'
OR option_name = 'siteurl';

# Changer l'URL des GUID
UPDATE wp_posts
SET guid = REPLACE (guid, ':old',':new');

# Changer l'URL des médias dans les articles et pages
UPDATE wp_posts
SET post_content = REPLACE (post_content,':old', ':new');

# Changer l'URL des données meta
UPDATE wp_postmeta
SET meta_value = REPLACE (meta_value, ':old',':new');