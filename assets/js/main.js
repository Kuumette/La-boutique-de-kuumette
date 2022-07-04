/**
 * Fonction addProduct qui permette de mettre à jour l'article, la quantité et la taille dans le panier
 */
function addProduct(idProduct, qtProduct, sizeProduct) {
	fetch(
		`http://127.0.0.1:8000/api/cart/add/${idProduct}/${qtProduct}/${sizeProduct}`,
		{
			method: "post",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				quantity: qtProduct,
				size: sizeProduct,
			}),
		}
	).then((res) => res.text());
}
/**
 * Au chargement du DOM
 */
document.addEventListener("DOMContentLoaded", () => {
	/**
	 * Constante qui recupere mon element du DOM avec la class atc-btn
	 */
	const atcButton = document.querySelectorAll(".atc-btn");
	atcButton.forEach((button) => {
		/**
		 * Evenement au click
		 */
		button.addEventListener("click", (e) => {
			e.preventDefault();
			/**
			 * Constante articleId qui recupere l'id du produit
			 */
			const articleId = button.dataset.product;
			/**
			 * constante qt qui recupere la valeur de mon input de type number
			 */
			const qt = document.querySelector(
				"#product-" + articleId + ' input[type="number"]'
			).value;
			/**
			 * Constante size qui recupere la valeur de mon select
			 */
			const size = document.querySelector(
				"#product-" + articleId + " select"
			).value;
			/**
			 * Apelle de ma fonction addProduct
			 */
			addProduct(articleId, qt, size);
		});
	});
});
