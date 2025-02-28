const Card = ({ product }) => {
    return (
        <li key={product.id}>
            {product.name} - ${product.price}
        </li>
    );
};

export default Card;
