<?php

require_once "db.php";

$stm = $pdo->prepare("INSERT INTO members (name, student_id, department, university, about_me, contributions) VALUES (?, ?, ?, ?, ?, ?)");

$stm->execute([
    "Chen Ping Yun",
    "413850065",
    "Computer Science",
    "Tamkang University",
    "Hello, I am a computer science student.",
    "To be filled later"
]);

$stm->execute([
    "Anika Nandakumar",
    "413855064", 
    "Computer Science", 
    "Tamkang University", 
    "I like math and coding. My hobbies are dancing, listening to music and reading non-fictional books. I like playing badminton. I'm exited to learn Open Source Practice.", 
    "To be filled later"
]);

$stm->execute([
    "Lai huien",
    "413856021", 
    "Computer Science and Information Engineering", 
    "Tamkang University", 
    "I am a second year student from Indonesia", 
    "To be filled later"
]);

$stm->execute([
    "Hinaka Narita",
    "413855650", 
    " CSIE", 
    " tku", 
    " Hello! My name is Hinaka. <br>I'm gonna be 21 this month, I can't believe I'm still alive till now.<br> My favorite food is sushi! I looove sushi. Do you know my dream?<br> YES! My dream is to die with sushi OR eat sushi every weekend, but I am always broke.🥹<br> If I can be anything, I'm gonna choose to be Superwoman.<br> I just wanna fly in the big sky without wings. What about you?", 
    "To be filled later"
]);
