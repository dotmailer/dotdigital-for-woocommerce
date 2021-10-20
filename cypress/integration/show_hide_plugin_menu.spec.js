describe('Show/hide plugin menu', () => {
  beforeEach('Activate Dotdigital for woocommerce plugin', () => {
    cy.activatePlugin('dotdigital-for-woocommerce')
    cy.visitAdmin()
  })
  it('dotdigital menu item exists', () => {
    cy.contains('Dotdigital for WooCommerce')
  })
  it('dotdigital menu item does not exist', () => {
    cy.deactivatePlugin('dotdigital-for-woocommerce')
    cy.reload()
    cy.contains('Dotdigital for WooCommerce').should('not.exist')
  })
})
