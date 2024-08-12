
const user = {
  name    : 'ahmed',
  email   : 'example@x.com',
  password: '123456789',
};

console.log(JSON.stringify(user))

fetch('http://localhost:80/MaleFasion/test.php', {
  method: 'POST',
  headers: {
      'Content-Type': 'application/json'
  },
  body: JSON.stringify(user)
})
// .then(response => response.json())
// .then(data => {
//   console.log('User created:', data);
//   // Update the UI or notify the user
// })
.catch(error => console.error('Error creating user:', error))