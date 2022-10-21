
const resolvers = {
    Query: {
        breeds: (parent, { id }, context, info) => {
            return breeds.find(images => images.breed_id === id);
        },
        users: (parent, args, context, info) => {
            return users;
        },
        images: (parent, args, context, info) => {
            return images;
        },
    },
};

export default resolvers;
