describe('Show/hide plugin menu', () => {
  beforeEach('Activate dotdigital for woocommerce plugin', () => {
    cy.activatePlugin('dotdigital-for-woocommerce')
    cy.visitAdmin()
  })
  it('dotdigital menu item exists', () => {
    cy.contains('dotdigital for WooCommerce')
  })
  it('dotdigital menu item does not exist', () => {
    cy.deactivatePlugin('dotdigital-for-woocommerce')
    cy.reload()
    cy.contains('dotdigital for WooCommerce').should('not.exist')
  })
})
