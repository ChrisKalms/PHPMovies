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