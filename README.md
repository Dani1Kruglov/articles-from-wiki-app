<h1>Wikipedia Article Management System</h1>

    <h2>Contents</h2>

    <ul>
        <li>Features</li>
        <li>Requirements</li>
        <li>Installation</li>
        <li>Usage</li>
        <li>Database Structure</li>
        <li>Technologies Used</li>
        <li>Additional Notes</li>
    </ul>

    <h2>Features</h2>

    <h3>Article Import</h3>
    <ul>
        <li>Two tabs: Article Import and Search.</li>
        <li>Switch between tabs without page reload.</li>
        <li>Enter a keyword and click "Copy" to search for an article on Wikipedia.</li>
        <li>Copy the contents of articles from Wikipedia, including plain text, into the internal database.</li>
        <li>Tokenization of copied articles into atomic words (sequences of [a-Zа-Я0-9] characters).</li>
        <li>Display summary information and update the results table without page reload.</li>
    </ul>

    <h3>Search</h3>
    <ul>
        <li>Search functionality based on the internal database.</li>
        <li>Exact matching of atomic words.</li>
        <li>Atomic words are stored in a separate table with an intermediate table for articles (word_id, article_id, count).</li>
        <li>Search results are ordered by the number of occurrences of the keyword in articles.</li>
    </ul>

    <h3>Article Display</h3>
    <ul>
        <li>Clicking on an article title opens its content on the right, without page reload and without using an iframe.</li>
    </ul>

    <h2>Requirements</h2>

    <h3>Technical Requirements</h3>
    <ul>
        <li>PHP 8.1+</li>
        <li>Laravel (or any PHP framework of your choice)</li>
        <li>MySQL with InnoDB tables and foreign keys</li>
        <li>CSS framework (Bootstrap)</li>
        <li>Code adhering to the MVC principles</li>
        <li>Code following PSR coding standards</li>
        <li>PHPDoc comments for code documentation</li>
    </ul>

    <h2>Installation</h2>
    <ol>
        <li>Clone the repository to your local computer.</li>
        <li>Configure your web server to point to the project's public directory.</li>
        <li>Create a MySQL database for the application.</li>
        <li>Update the .env file with your database credentials.</li>
        <li>Run the following commands to set up the database:</li>
    </ol>
    <pre>
        <code>
        php artisan migrate
        php artisan db:seed
        </code>
    </pre>

    <h2>Usage</h2>
    <ol>
        <li>Open the application in your web browser.</li>
        <li>Use the "Article Import" tab to search for and copy articles from Wikipedia.</li>
        <li>Copied articles will be saved in the internal database for later use.</li>
        <li>Switch to the "Search" tab to perform searches based on atomic words.</li>
        <li>Click on an article title to view its content on the right.</li>
    </ol>

    <h2>Database Structure</h2>
    <p>The database includes the following tables:</p>
    <ul>
        <li>articles (id, title, content, url, size_article, word_count)</li>
        <li>words_of_articles (id, word, article_id, number_of_words)</li>
    </ul>
    <p>These tables facilitate the storage and retrieval of articles from Wikipedia.</p>

    <h2>Technologies Used</h2>
    <ul>
        <li>PHP</li>
        <li>Laravel (or your chosen PHP framework)</li>
        <li>MySQL</li>
        <li>HTML/CSS (Bootstrap)</li>
        <li>JavaScript (for dynamic interface updates)</li>
    </ul>
