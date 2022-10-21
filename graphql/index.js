const { ApolloServer, gql } = require('apollo-server')

const breeds = require('./resolvers/breeds')
const randomPictureForBreed = require('./resolvers/randomPictureForBreed')

// Construct a schema, using GraphQL schema language
const typeDefs = gql`
    type Query {
        breeds: [String!]!
        randomPictureForBreed(breed: String!): String!
        park: [String!]!
    }
`

// Provide resolver functions for your schema fields
const resolvers = {
    Query: {
        breeds,
        randomPictureForBreed,
        park,
    },
}

const server = new ApolloServer({
    typeDefs,
    resolvers,
})

server.listen().then(({ url }) => {
    console.log(`🚀 Server ready at ${url}`)
})
