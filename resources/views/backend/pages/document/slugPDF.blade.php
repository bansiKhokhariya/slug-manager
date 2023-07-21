<!DOCTYPE html>
<html>

<head>
    <title>Document PDF</title>
</head>

<body>
    <h1>Title: {{ $document_details['title'] }}</h1>
    <p>Slug Category: {{ $document_details['category'] }}</p>
    <p>Sub Title: {{ $document_details['slug'] }}</p>
    <p>Topic: {{ $document_details['topic'] }}</p>
    <p>Sub Topic: {{ $document_details['subTopic'] }}</p>
    <p>Content: {{ $document_details['content'] }}</p>
    <p>Reference: {{ $document_details['reference'] }}</p>
</body>

</html>
