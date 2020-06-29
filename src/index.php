<!DOCTYPE html>
<html>
<head>
  <title>PHP Movies</title>  
  <link rel="stylesheet" href="./style.css"/> 
</head>

<body>

  <div class="page">

  <div class="header-wrapper">
    <h1 class="header">Php Movies Database</h1>
  </div> <!-- close header-wrapper -->

  <form id="search-movie-form"></form>

  <div class="content">

    <div class="search-wrapper">
      <div class="search-field-wrapper">        
        Title:
        <input form="search-movie-form" id="title" class="search-field" type="text" name="title">     
      </div> <!--end search-field-wrapper-->

      <div class="search-field-wrapper">        
        Genre:
        <input form="search-movie-form" id="genre" class="search-field" type="text" name="genre">    
      </div> <!--end search-field-wrapper-->

      <div class="search-field-wrapper">          
        Rating:
        <input form="search-movie-form" id="rating" class="search-field" type="text" name="rating">      
      </div> <!--end search-field-wrapper-->

      <div class="search-field-wrapper">    
        Year:
        <input form="search-movie-form" id="year" class="search-field" type="text" name="year">        
      </div> <!--end search-field-wrapper-->          

      <input form="search-movie-form" class="submit-button" type="button" id="submit-form" value="Search Database">       

    </div>  <!--end search-wrapper-->
    
    
    <div class="table-wrapper">
      <table id="movies-table" class="display-table">
        <tr>         
          <th>Title</th>
          <th>Studio</th>
          <th>Status</th> 
          <th>Sound</th> 
          <th>Versions</th>
          <th>RecRetPrice</th> 
          <th>Rating</th> 
          <th>Year</th>
          <th>Genre</th> 
          <th>Aspect</th>
        </tr>
        <tbody id="table-body">

        </tbody>
      </table> 
    </div> <!--end table-->

  </div> <!--end content-->
</div> <!-- end page -->

<!--?php require_once('movies_data.php'); ?-->

<script>   
  const table = document.getElementById('movies-table');
  const tbody = document.getElementById('table-body');
  const searchBtn = document.getElementById('submit-form');
  const title = document.getElementById('title');
  const genre = document.getElementById('genre');
  const rating = document.getElementById('rating');
  const year = document.getElementById('year');

  searchBtn.addEventListener('click', () => {
    getMovies();
    clearInputs();
  });

  let moviesArray;
  async function getMovies() {
    try {
      // call movies_data with data from text boxes.
      const result = await fetch(`
      ./movies_data.php?
        title=${title.value}&
        genre=${genre.value}&
        rating=${rating.value}&
        year=${year.value}`
      );
      
      const m = await result.json();        
      printMovies(m);
      return m;

    } catch (error) {
      console.log(error);
    }
  }

  getMovies().then(value => {
    moviesArray = value;    
  });  
  
  
  function printMovies(movies){
    // clear the table for new movies
    tbody.innerHTML = '';   

    // fill the table with new data - limit 15
    // don't exceed the length of movies
    for(let k = 0; k < 15 && k < movies.length; k++) {
      let movie = movies[k];
      
      let html = `<tr class="row-${k % 2}">
      <td>${movie.Title}</td>
      <td>${movie.Studio}</td>
      <td>${movie.Status}</td>
      <td>${movie.Sound}</td>
      <td>${movie.Versions}</td>
      <td>${movie.RecRetPrice}</td>
      <td>${movie.Rating}</td>
      <td>${movie.Year}</td>
      <td>${movie.Genre}</td>
      <td>${movie.Aspect}</td>    
      <tr>`;   
      
      //insert into the table before the closing tag
      tbody.insertAdjacentHTML('beforeend', html);
    }
  }  


  function clearInputs(){    
    title.value = '';
    genre.value = '';
    rating.value = '';
    year.value = '';
  }
</script>

</body>
</html>


