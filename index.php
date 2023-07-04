<?php

function generateGmailIDs($baseID, $numIDs, $trick) {
    $gmailIDs = [];
    
    // Split the base Gmail ID to get the username and domain
    $parts = explode('@', $baseID);
    $username = $parts[0];
    $domain = $parts[1];
    
    $usernameLength = strlen($username);
    
    // Calculate the number of possible combinations
    $numCombinations = pow(2, $usernameLength - 1);
    
    // Generate Gmail IDs using the selected trick
    $count = 0; // Track the number of generated IDs
    for ($i = 0; $i < $numCombinations && $count < $numIDs; $i++) {
        $gmailID = '';
        for ($j = 0; $j < $usernameLength; $j++) {
            $gmailID .= $username[$j];
            if ($trick === 'dot' && (($i >> $j) & 1)) {
                $gmailID .= '.';
            } else if ($trick === 'plus' && (($i >> $j) & 1)) {
                $gmailID .= '+';
            }
        }
        $gmailIDs[] = $gmailID . '@' . $domain;
        $count++;
    }
    
    return $gmailIDs;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the base Gmail ID, trick, and number of Gmail IDs from the form submission
    $baseGmailID = $_POST['base_id'];
    $trick = $_POST['trick'];
    $numGeneratedIDs = (int)$_POST['num_ids'];

    // Generate Gmail IDs
    $generatedGmailIDs = generateGmailIDs($baseGmailID, $numGeneratedIDs, $trick);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unlimited Gmail Generator</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }
        
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        
        h3 {
            margin-top: 20px;
        }
        
        .gmail-id {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Unlimited Gmail Generator</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="base_id">Main Gmail ID:</label>
                    <input type="text" name="base_id" id="base_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="trick">Trick:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="trick" id="dot_trick" value="dot" checked>
                        <label class="form-check-label" for="dot_trick">Dot Trick (.)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="trick" id="plus_trick" value="plus">
                        <label class="form-check-label" for="plus_trick">Plus Trick (+)</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="num_ids">Number of Gmail IDs:</label>
                    <select name="num_ids" id="num_ids" class="form-control" required>
                        <option value="1">1</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="70">70</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Generate</button>
            </form>

            <?php if (isset($generatedGmailIDs)) : ?>
                <h3>Generated Gmail IDs:</h3>
                <?php foreach ($generatedGmailIDs as $gmailID) : ?>
                    <p class="gmail-id">
                        <?php 
                        echo "<hr>";
                        echo $gmailID;
                         ?>
                        <button class="btn btn-sm btn-secondary" onclick="copyToClipboard(this)" data-email="<?php echo $gmailID; ?>">Copy</button>
                    </p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function copyToClipboard(button) {
            const email = button.getAttribute('data-email');
            const tempInput = document.createElement('input');
            tempInput.value = email;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('Copied: ' + email);
        }
    </script>
</body>
</html>
