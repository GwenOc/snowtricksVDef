<a href="https://codeclimate.com/github/GwenOc/snowtricksVDef/maintainability"><img src="https://api.codeclimate.com/v1/badges/99cea039eed95b3f8b73/maintainability" /></a>

# snowtricksVDef
Remote for Project3-Openclassroom Developpeur Web Junior

# Technologie
This project use Symfony 4.3 and PHP 7.1.3

# How to install
<ul>
<li>Clone the project :</li>

<div class="highlight highlight-source-shell"><pre>git clone https://github.com/GwenOc/snowtricksVDef.git</pre></div>

<li>Install dependencies : </li>

<code>composer install</code>

<li>Configure the .env by switching db_user, db_password and db_name</li>

<div class="highlight highlight-source-shell"><pre>DATABASE_URL=mysql: //db_user:db_password@127.0.0.1:3306/db_name</pre></div>

<li>Create the database</li>

<code>php bin/console doctrine:database:create</code>

<li>UpDate schema</li>

<code>php bin/console doctrine:schema:update --force</code>

<li>Load the dataFixtures</li>

<code>php bin/console doctrine:fixtures:load</code>

</ul>

# Here we go !
