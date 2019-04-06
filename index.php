<?php
require_once 'curl.class.php';
$obj = new curl();
$data = $obj->get(
    'https://uifaces.co/api',
    array(
        'limit' => 30,
        'emotion' => array(
            'happiness',
        ),
        'random' => true,
    ),
    '55aa6dbdc77b72fbf1e91fa7ea3ba9'
);
if ($data) { ?>
    <link rel="stylesheet" href="styles.min.css">
    <ul>
        <?php foreach ($data as $dat) { ?>
            <li>
                <a href="mailto:<?php echo $dat->email; ?>" target="_blank" rel="noopener noreferrer nofollow" style="background-image: url(<?php echo $dat->photo; ?>)"><span><?php echo $dat->name; ?><br><small><?php echo $dat->position; ?></small></span></a>
            </li>
        <?php } ?>
    </ul>
<?php } ?>