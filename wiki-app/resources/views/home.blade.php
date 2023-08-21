@extends('layouts.app')

@section('content')
    <div class="container">
        <ul class="nav nav-tabs" id="myTabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#importTab">Импорт статей</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#searchTab">Поиск</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active mt-3" id="importTab">
                <h3>Импорт статей</h3>
                <div class="mb-3 w-50">
                    <label for="pageTitle" class="form-label">Скопировать статью</label>
                    <input type="text" class="form-control" id="pageTitle" name="pageTitle">
                    <div id="emailHelp" class="form-text">Введите название статьи</div>
                </div>
                <button type="button" id="copy-button" class="btn btn-primary">Скопировать</button>
                <div class="mt-3">
                    <div id="dangerDiv" style="display: none; color: red">Нет статьи с таким названием</div>
                    <div id="warningDiv" style="display: none; color: orange">Статья с таким названием уже была скопирована</div>
                    <table class="table" id="articlesTable">
                        <thead>
                        <tr>
                            <th>Название статьи</th>
                            <th>Ссылка</th>
                            <th>Размер статьи</th>
                            <th>Количество слов</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($articles as $article)
                            <tr>
                                <td>{{$article->title}}</td>
                                <td><a href="{{$article->url}}" style="text-decoration: none; color: blue">{{$article->url}}</a></td>
                                <td>{{$article->size_article}} bytes</td>
                                <td>{{$article->word_count}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade mt-3" id="searchTab">
                <h3>Поиск статей</h3>
                <div class="mb-3 w-50">
                    <label for="word" class="form-label">Поиск статей по слову</label>
                    <input type="text" class="form-control" id="word" name="word">
                    <div id="emailHelp" class="form-text">Введите слово статьи</div>
                </div>
                <button type="submit" id="search-button" class="btn btn-primary">Найти</button>
                <ul class="list-group" id="articles-list" style="margin-top: 50px"></ul>
                <div id="content-container" style="background: darkgray"></div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $("#myTabs a").click(function(e){
                e.preventDefault();
                $(this).tab('show');
            });
            $('#copy-button').on('click', function () {
                var pageTitle = $('#pageTitle').val();
                $.ajax({
                    url: "{{ route('copy.article') }}",
                    method: "POST",
                    data: {
                        pageTitle: pageTitle
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        var article = response.article;
                        if (article === 'previously created'){
                            $('#warningDiv').show();
                            setTimeout(function() {
                                $('#warningDiv').hide();
                            }, 10000);
                           }else if (article === 'no article') {
                            $('#dangerDiv').show();
                            setTimeout(function() {
                                $('#dangerDiv').hide();
                            }, 10000);
                        } else {
                            var table = $('#articlesTable');
                            table.prepend(`<tr><td>${article.title}</td><td><a href="${article.url}" style="text-decoration: none; color: blue">${article.url}</a></td><td>${article.size_article} bytes</td><td>${article.word_count}</td></tr>`);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(status)
                        console.log(error)
                    }
                });
            });

            $('#search-button').on('click', function () {
                var word = $('#word').val();
                $.ajax({
                    url: "{{ route('search.article') }}",
                    method: "PATCH",
                    data: {
                        word: word
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        var articles = response.articles;
                        var articleList = document.getElementById('articles-list');
                        articleList.innerHTML = '';
                        articles.forEach(function(article) {
                            var listItem = document.createElement('li');
                            listItem.className = 'list-group-item';
                            listItem.innerHTML = '<a href="'+article.url+'" >' + article.title + '</a href="" ><span style="color: grey; margin-left: 10%">(' + article.number_of_words  + ' вхождений)</span>';
                            articleList.appendChild(listItem);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(status);
                        console.log(error);
                    }
                });
            });

        });
    </script>
@endsection
