<div class="pt20">
    <!--            <div class="nNote nWarning hideit">
                    <p><strong>WARNING: </strong>This is a warning message. You can use this to warn users on any events</p>
                </div>
                <div class="nNote nInformation hideit">
                    <p><strong>INFORMATION: </strong>This is a message for information, can be any general information.</p>
                </div>   -->
    <?php if (isset($msg) AND $msg != ''): ?>
        <div class="nNote nSuccess hideit">
            <p><strong>SUCCESS: </strong><?php echo $msg; ?></p>
        </div> 
    <?php endif; /* isset($msg) AND $msg !='' */ ?>
    
    <?php if (isset($err) AND $err != ''): ?>
        <div class="nNote nFailure hideit">
            <p><strong>FAILURE: </strong><?php echo $err; ?></p>
        </div>
    <?php endif; /* isset($err) AND $err !='' */ ?>
</div>