const fetch = require('node-fetch')

const randomPictureForBreed = (_, { breed }) =>
    fetch(`https://dog.ceo/api/breed/${breed.replace(/\s/g, '/')}/images/random`)
        .then((response) => response.json())
        .then(({ message }) => message)

module.exports = randomPictureForBreed
