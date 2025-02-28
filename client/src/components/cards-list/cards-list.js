import React, { useEffect, useState } from "react";
import { fetchProducts } from "../../api";
import Card from "../card/card";

const CardsList = () => {
    const [products, setProducts] = useState([]);

    useEffect(() => {
        fetchProducts().then(setProducts);
    }, []);

    return (
        <div>
            <h1>Product List</h1>
            <ul>
                {products.map((product) => (
                    <Card product={product} />
                ))}
            </ul>
        </div>
    );
};

export default CardsList;
