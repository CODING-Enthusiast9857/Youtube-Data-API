<?php
    if(isset($_POST['submit']))
    {
        $API_Key='AIzaSyANmcujJeVX0yZ-jxIVq3634uhFSzH_Nac';
        $API_url='https://www.googleapis.com/youtube/v3/';
        $channelid=$_POST['channelid'];

        /*****
        To fetch Youtube data : 
        Channel Logo
        Channel Name, Description, 
        Subscribers count, Views count & Videos count
        *****/
        $params=array
        (
            'part' => 'snippet,contentDetails,statistics',
            'id'   =>  $channelid,
            'key'  =>  $API_Key
        );

        $url=$API_url.'channels?'.http_build_query($params);

        $channelDetails=json_decode(file_get_contents($url),true);

        /*****
        Fetching data into particular variables
        *****/
        $logo=$channelDetails['items'][0]['snippet']['thumbnails']['medium']['url'];
        $title=$channelDetails['items'][0]['snippet']['title'];
        $desc=$channelDetails['items'][0]['snippet']['description'];
        $subs=$channelDetails['items'][0]['statistics']['subscriberCount'];
        $views=$channelDetails['items'][0]['statistics']['viewCount'];
        $videos=$channelDetails['items'][0]['statistics']['videoCount'];

        /***** 
        To fetch videos 
        *****/
        $params=array
        (
            'part'       => 'snippet,contentDetails',
            'playlistId' => $channelDetails['items'][0]['contentDetails']['relatedPlaylists']['uploads'],
            'key'        => $API_Key,
            'maxResults' => 50
        );                   
        
        $url=$API_url.'playlistItems?'.http_build_query($params);

        $playlistDetails=json_decode(file_get_contents($url),true);

        $data=$playlistDetails['items'];
    }  
    else
    {
        $data=[];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Data | Youtube Data API v3</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="icon" href="assets/logo.png">
    <style>
        .mb-5{
            color:rgb(255,0,0);
        }
    </style>
</head>
<body>
    <section class="container py-5 text-center">
        <div class="row mb-5">
            <div class="col-6 mx-auto">
                <h1 class="mb-5">Fetch Youtube Data by ID</h1>
                <form action="" method="POST">
                    <div class="form-group mb-5">
                        <input type="text" class="form-control" placeholder="Enter channel ID" name="channelid">
                    </div>
                    <input class="btn btn-danger" value="GET DATA" type="submit" name="submit">
                </form>
            </div>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>Channel Logo</th>
                <th>Channel Name</th>
                <th>Description</th>
                <th>Subscribers count</th>
                <th>Views Count</th>
                <th>Videos Count</th>
            </tr>
            <tr>
                <td><img src="<?php echo isset($logo)?$logo:''?>" alt=""></td>
                <td><?php echo isset($title)?$title:''?></td>
                <td><?php echo isset($desc)?$desc:''?></td>
                <td><?php echo isset($subs)?$subs:''?></td>
                <td><?php echo isset($views)?$views:''?></td>
                <td><?php echo isset($videos)?$videos:''?></td>
            </tr>
        </table>
    </section>

    <section class="container">
        <div class="row">
            <?php foreach($data as $video){
            $id=$video['contentDetails']['videoId'];?>
            <div class="col-3 mb-5">
                <iframe width="100%" height="200" src="https://www.youtube.com/embed/<?php echo $id?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <?php } ?>
        </div>
    </section>
</body>
</html>